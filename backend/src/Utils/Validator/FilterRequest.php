<?php

namespace App\Utils\Validator;

use App\Entity\User\User;
use App\Utils\Tools\APIJsonResponse;
use App\Utils\Validator\BaseRequest;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FilterRequest extends BaseRequest {


    public function __construct(
        ValidatorInterface $validator,
        Security $token,
        JWTTokenManagerInterface $jwtManager
    )
    {
        parent::__construct($validator, $token, $jwtManager);

    }
    protected ?array $filter_filters = [];

    protected ?array $filter_order = [];

    protected ?string $current_request = "";

    protected ?int $page = 1;

    protected ?int $limit = 25;


    /**
     * @throws JWTDecodeFailureException
     */
    protected function populate(): void
    {
        parent::populate();
        /** @var User $user */
        $user = $this->token->getUser();
    }
    protected function includeQueryParams(): bool
    {
        return true;

    }

}