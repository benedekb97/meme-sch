<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Entities\ImageInterface;
use App\Services\Repository\ImageRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Arr;
use Intervention\Image\Constraint;
use Intervention\Image\ImageManager;

class ConvertImageJob
{
    public const TARGET_WIDTH_XS = 32;
    public const TARGET_WIDTH_S = 64;
    public const TARGET_WIDTH_M = 256;
    public const TARGET_WIDTH_L = 512;
    public const TARGET_WIDTH_XL = 1024;

    public const TARGET_WIDTHS = [
        self::TARGET_WIDTH_XS,
        self::TARGET_WIDTH_S,
        self::TARGET_WIDTH_M,
        self::TARGET_WIDTH_L,
        self::TARGET_WIDTH_XL,
    ];

    private ImageRepositoryInterface $imageRepository;

    private ImageManager $imageManager;

    private Filesystem $filesystem;

    private EntityManagerInterface $entityManager;

    public function __construct(
        ImageRepositoryInterface $imageRepository,
        ImageManager             $imageManager,
        FilesystemManager        $filesystemManager,
        EntityManager            $entityManager
    )
    {
        $this->imageRepository = $imageRepository;
        $this->imageManager = $imageManager;
        $this->filesystem = $filesystemManager->disk(config('filesystems.default'));
        $this->entityManager = $entityManager;
    }

    public function __invoke(): void
    {
        $images = $this->imageRepository->findAllNonConverted();

        /** @var ImageInterface $image */
        foreach ($images as $image) {
            $imageObject = $this->imageManager->make(
                $this->filesystem->get($image->getFilePath())
            );

            $fileName = Arr::last(explode(DIRECTORY_SEPARATOR, $image->getFilePath()));

            $sourceSets = [];

            foreach (self::TARGET_WIDTHS as $width) {
                if ($imageObject->getWidth() > $width) {
                    $convertedImageObject = clone $imageObject;

                    $convertedImageObject->resize($width, null, function (Constraint $constraint) {
                        $constraint->aspectRatio();
                    });

                    $convertedImageObject->save(
                        storage_path(
                            'app/' . $sourceSets[$width] = sprintf(
                                'images/%d_%s',
                                $width,
                                $fileName
                            )
                        )
                    );
                } else {
                    $width = $imageObject->getWidth();

                    $convertedImageObject = clone $imageObject;

                    $convertedImageObject->resize($width, null, function (Constraint $constraint) {
                        $constraint->aspectRatio();
                    });

                    $convertedImageObject->save(
                        storage_path(
                            'app/' . $sourceSets[$width] = sprintf(
                                'images/%d_%s',
                                $width,
                                $fileName
                            )
                        )
                    );

                    break;
                }
            }

            $image->setSourceSet($sourceSets);

            $this->entityManager->persist($image);
        }

        $this->entityManager->flush();
    }
}
