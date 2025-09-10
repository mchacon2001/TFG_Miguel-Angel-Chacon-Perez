<?php

namespace App\Request\EducativeResources;

use App\Services\EducativeResource\EducativeResourcesService;
use App\Services\User\UserPermissionService;
use App\Utils\Validator\BaseRequest;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GetEducativeResourceRequest extends BaseRequest
{

    #[NotBlank]
    #[NotNull]
    public string $educativeResourceId;


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

        $educativeResource = $this->educativeResourceService->getEducativeResourceById($this->educativeResourceId);

        if(!$educativeResource)
        {
            $this->addError("educativeResourceId", "El recurso educativo no existe");
            $this->resolveRequest();
        }
    }
}