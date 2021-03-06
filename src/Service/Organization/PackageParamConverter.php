<?php

declare(strict_types=1);

namespace Buddy\Repman\Service\Organization;

use Buddy\Repman\Query\User\Model\Package;
use Buddy\Repman\Query\User\PackageQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PackageParamConverter implements ParamConverterInterface
{
    private PackageQuery $packageQuery;

    public function __construct(PackageQuery $packageQuery)
    {
        $this->packageQuery = $packageQuery;
    }

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === Package::class;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        if (null === $id = $request->attributes->get('package')) {
            throw new BadRequestHttpException('Missing package parameter in request');
        }

        $request->attributes->set(
            $configuration->getName(),
            $this
                ->packageQuery
                ->getById($id)
                ->getOrElseThrow(new NotFoundHttpException('Package not found'))
        );

        return true;
    }
}
