<?php
namespace App\Attribute;


use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Permission
{

    public function __construct(
        public string $group,
        public string $action
    )
    {

    }


    /**
     * @return mixed
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return mixed
     */
    public function getGroup(): string
    {
        return $this->group;
    }




}