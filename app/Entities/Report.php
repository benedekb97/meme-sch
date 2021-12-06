<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Traits\PostAwareTrait;
use App\Entities\Traits\ResourceTrait;
use App\Entities\Traits\TimestampableTrait;
use App\Entities\Traits\UserAwareTrait;

class Report implements ReportInterface
{
    use ResourceTrait;
    use TimestampableTrait;
    use PostAwareTrait;
    use UserAwareTrait;

    private ?string $reason = null;

    private ?string $status = null;

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): void
    {
        $this->reason = $reason;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }
}
