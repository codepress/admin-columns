<?php

declare(strict_types=1);

namespace AC\Notice;

use AC\Expression\SpecificationFactory;

final class Repository
{

    private const ID = 'id';
    private const TYPE = 'type';
    private const MESSAGE = 'message';
    private const CONDITIONS = 'conditions';

    private SpecificationFactory $specification_factory;

    public function __construct(SpecificationFactory $specification_factory)
    {
        $this->specification_factory = $specification_factory;
    }

    public function get_next_identity(): string
    {
        return wp_generate_uuid4();
    }

    public function save(Notice $notice): void
    {
        $notices = $this->read();

        $notices[$notice->get_id()] = [
            self::ID         => $notice->get_id(),
            self::TYPE       => $notice->get_type(),
            self::MESSAGE    => $notice->get_message(),
            self::CONDITIONS => $notice->get_conditions()->export(),
        ];

        $this->update($notices);
    }

    public function find(string $id): ?Notice
    {
        $notices = $this->read();

        return $notices[$id] ?? null;
    }

    /**
     * @param array $context Can contain AC\Screen, current user or other relevant information
     *
     * @return Notice[]
     */
    public function find_all(array $context = []): array
    {
        $notices = [];

        foreach ($this->read() as $dto) {
            $notice = new Notice(
                $dto[self::ID],
                $dto[self::TYPE],
                $dto[self::MESSAGE],
                $this->specification_factory->create($dto[self::CONDITIONS])
            );

            if ( ! $context || $notice->get_conditions()->is_satisfied_by($context)) {
                $notices[$notice->get_id()] = $notice;
            }
        }

        return $notices;
    }

    public function delete(string $id): void
    {
        $notices = $this->read();

        unset($notices[$id]);

        $this->update($notices);
    }

    private function update(array $notices): void
    {
        update_option($this->get_key(), $notices, false);
    }

    private function read(): array
    {
        return get_option($this->get_key(), []);
    }

    private function get_key(): string
    {
        return 'ac_notices';
    }

}