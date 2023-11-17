<?php
declare(strict_types=1);

namespace Actio\Entity;

use Psr\Log\LogLevel;

class DataPoint
{
    private array $activity;
    private array $actor;
    private array $context;
    private \DateTimeImmutable $date;
    private LogLevel $level;
    private string $summary;
    private array $target;

    public function __construct()
    {
    }

    public function toArray(): array
    {
        return [
            'activity' => $this->activity,
            'actor'    => $this->actor,
            'context'  => $this->context,
            'date'     => $this->date->format('Y-m-d\TH:i:s\Z'),
            'level'    => $this->level,
            'summary'  => $this->summary,
            'target'   => $this->target,
        ];
    }
}
