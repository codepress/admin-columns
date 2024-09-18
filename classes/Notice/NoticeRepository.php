<?php

declare(strict_types=1);

namespace AC\Notice;

use AC\Expression\SpecificationFactory;

class NoticeRepository
{

    private const TYPE = 'type';
    private const MESSAGE = 'message';
    private const CONDITIONS = 'conditions';

    private SpecificationFactory $specification_factory;

    public function __construct( SpecificationFactory $specification_factory )
    {
        $this->specification_factory = $specification_factory;
    }

    public function save(Notice $notice): void
    {
        $dto = [
            self::TYPE => $notice->get_type(),
            self::MESSAGE => $notice->get_message(),
            self::CONDITIONS => $notice->get_conditions()->export(),
        ];

        // TODO David store
    }

    /**
     * @param array $context Can contain AC\Screen, current user or other relevant information
     *
     * @return array
     */
    public function find_all( array $context = [] ): array
    {

    }

}