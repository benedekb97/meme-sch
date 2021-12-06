<?php

declare(strict_types=1);

namespace App\Services\Factory;

use App\Entities\PostInterface;
use App\Entities\Report;
use App\Entities\ReportInterface;
use App\Entities\UserInterface;

class ReportFactory implements ReportFactoryInterface
{
    public function createForUserAndPost(UserInterface $user, PostInterface $post): ReportInterface
    {
        $report = new Report();

        $report->setUser($user);
        $post->addReport($report);

        return $report;
    }
}
