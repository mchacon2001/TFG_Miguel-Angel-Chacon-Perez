<?php

namespace App\Utils\Validator;

use App\Utils\Classes\JWTHandlerService;
use App\Utils\Tools\APIJsonResponse;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseRequest extends JWTHandlerService
{
    private array $errors = [];

    /**
     * @throws JWTDecodeFailureException
     */
    public function __construct(
        protected ValidatorInterface       $validator,
        protected Security                 $token,
        protected JWTTokenManagerInterface $jwtManager
    )
    {
        parent::__construct($token, $jwtManager);

        $this->populate();

        if ($this->autoValidateRequest()) {
            $this->validate();
        }
    }

    public function validate(): void
    {
        $errors = $this->validator->validate($this);

        $messages = ['errors' => []];

        /** @var ConstraintViolation $errors */
        foreach ($errors as $message) {
            $messages['errors'][] = [
                'property' => $message->getPropertyPath(),
                'value'    => $message->getInvalidValue(),
                'message'  => $message->getMessage(),
            ];
        }

        if (count($messages['errors']) > 0) {
            $response = new APIJsonResponse($messages, false, $this->getErrorMessages($messages), 201);
            $response->headers->add([
                'Access-Control-Allow-Origin' => '*'
            ]);
            $response->send();
            exit();
        }
    }

    public function getRequest(): Request
    {
        return Request::createFromGlobals();
    }

    /**
     * @throws JWTDecodeFailureException
     */
    protected function populate(): void
    {

        try {
            $requestParams = $this->getRequest()->toArray();
        } catch (Exception $e) {
            $requestParams = $this->getRequest()->request->all();
        }

        if ($this->includeQueryParams()) {
            $requestParams = array_merge($requestParams, $this->getRequest()->query->all());
        }
        if ($this->includeFiles()) {
            $requestParams = array_merge($requestParams, $this->getRequest()->files->all());
        }


        $typesMap = $this->detectValidTypes();

        $parseEmptyToNull = false;
        if (@$this->getRequest()->headers->get("content-type") != "application/json") {
            $parseEmptyToNull = true;
        }

        foreach ($requestParams as $property => $value) {

            if ($parseEmptyToNull && !$value) {
                $value = null;
            }

            if (property_exists($this, $property)) {


                $this->{$property} = $value;

            }
        }
    }


    public function detectValidTypes(): array
    {
        $reflectionClass = new ReflectionClass(get_called_class());
        $typesMap        = []; 

        foreach ($reflectionClass->getProperties() as $property) {
            $attributes   = $property->getAttributes();
            $propertyName = $property->getName();
            if (count($attributes) > 0) {
                foreach ($attributes as $attribute) {
                    if ($attribute->getName() == Type::class) {
                        $typeValue = $attribute->getArguments()[0];
                        if ($typeValue) {
                            $typesMap[$propertyName] = $typeValue;
                        }
                    }
                }
            }
        }

        return $typesMap;
    }

    public function get(string $fieldName)
    {
        return @$this->{$fieldName};
    }

    protected function autoValidateRequest(): bool
    {
        return true;
    }

    protected function includeQueryParams(): bool
    {
        return false;
    }

    protected function includeFiles(): bool
    {
        return false;
    }

    protected function addError(string $property, string $message, $value = null): void
    {
        $this->errors[] = [
            'property' => $property,
            'value'    => $value,
            'message'  => $message,
        ];
    }

    protected function resolveRequest(): void
    {

        if (count($this->errors) > 0) {
            $messages = ['errors' => $this->errors];

            $response = new APIJsonResponse($messages, false, "Request error", 201);
            $response->headers->add([
                'Access-Control-Allow-Origin' => '*'
            ]);
            $response->send();
            exit();
        }
    }


    protected function getErrorMessages(array $messages): string
    {
        if(@$messages['errors']) {
            $errors = $messages['errors'];
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error['message'];
            }
            return implode(", ", $errorMessages);
        } else {
            return "Error de la solicitud";
        }
    }
}