<?php
declare(strict_types=1);

namespace Actio\Entity;

use DateTimeImmutable;

class DataPoint
{
    /** @var array<string, mixed> */
    private array $activity;

    /** @var array<string, mixed> */
    private array $actor;

    /** @var array<string, mixed>|null */
    private ?array $context;

    private ?DateTimeImmutable $date = null;
    private int|string|null $id = null;
    private ?string $level;
    private ?string $summary;

    /** @var array<string, mixed> */
    private array $target;

    /** @return array<string, mixed> */
    public function getActivity(): array
    {
        return $this->activity;
    }

    /** @param array<string, mixed> $activity */
    public function setActivity(array $activity): DataPoint
    {
        $this->activity = $activity;

        return $this;
    }

    /** @return array<string, mixed> */
    public function getActor(): array
    {
        return $this->actor;
    }

    /** @param array<string, mixed> $actor */
    public function setActor(array $actor): DataPoint
    {
        $this->actor = $actor;

        return $this;
    }

    /** @return array<string, mixed>|null */
    public function getContext(): ?array
    {
        return $this->context;
    }

    /** @param array<string, mixed>|null $context */
    public function setContext(?array $context): DataPoint
    {
        $this->context = $context;

        return $this;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?DateTimeImmutable $date): DataPoint
    {
        if ($date) {
            $this->date = $date;
        }

        return $this;
    }

    public function getId(): int|string|null
    {
        return $this->id;
    }

    public function setId(int|string|null $id): DataPoint
    {
        $this->id = $id;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(?string $level): DataPoint
    {
        $this->level = $level;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): DataPoint
    {
        $this->summary = $summary;

        return $this;
    }

    /** @return array<string, mixed> */
    public function getTarget(): array
    {
        return $this->target;
    }

    /** @param array<string, mixed> $target */
    public function setTarget(array $target): DataPoint
    {
        $this->target = $target;

        return $this;
    }

    public function __toString(): string
    {
        $json = json_encode($this->toArray());
        return $json === false ? '' : $json;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $data = [
            'activity' => $this->activity,
            'actor'    => $this->actor,
            'context'  => $this->context,
            'level'    => $this->level,
            'summary'  => $this->summary,
            'target'   => $this->target,
        ];

        if ($this->id!== null) {
            $data['id'] = $this->id;
        }
        if ($this->date!== null) {
            $data['date'] = $this->date->format('Y-m-d\TH:i:s\Z');
        }

        return $data;
    }
}
