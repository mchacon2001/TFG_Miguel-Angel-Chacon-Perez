<?php

namespace App\Request\EducativeResources;

use App\Services\EducativeResource\EducativeResourcesService;
use App\Services\User\UserPermissionService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditEducativeResourceRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    public string $educativeResourceId;

    #[NotBlank]
    #[NotNull]
    public string $title;

    #[NotBlank]
    #[NotNull]
    public string $youtubeUrl;
    public ?string $description = null;
    public ?bool $isVideo = false;
    
    #[NotNull]
    #[NotBlank]
    public string $tag;

    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected EducativeResourcesService $educativeResourceService,
                                protected UserPermissionService $userPermissionService)
    {
        parent::__construct($validator, $token, $jwtManager);
    }

    /**
     * @throws NonUniqueResultException
     */
 public function validate(): void
    {
        parent::validate();
    }
}