<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector;
use Rector\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\DeadCode\Rector\Property\RemoveUselessVarTagRector;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php80\Rector\ClassConstFetch\ClassOnThisVariableObjectRector;
use Rector\Php80\Rector\FuncCall\ClassOnObjectRector;
use Rector\Php80\Rector\FunctionLike\MixedTypeRector;
use Rector\Php81\Rector\Array_\FirstClassCallableRector;
use Rector\Php81\Rector\ClassConst\FinalizePublicClassConstantRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Php82\Rector\Class_\ReadOnlyClassRector;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictConstantReturnRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector;
use Rector\TypeDeclaration\Rector\FunctionLike\AddParamTypeSplFixedArrayRector;
use Rector\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector;

return static function (RectorConfig $rector): void {
    $rector->paths([__DIR__ . '/src', __DIR__ . '/tests']);
    $rector->skip([__DIR__ . '/tests/_support/_generated']);

    $rector->cacheClass(FileCacheStorage::class);
    $rector->cacheDirectory(__DIR__ . '/var/cache/rector');
    $rector->symfonyContainerXml(__DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml');

    // Php74
    $rector->rule(ClosureToArrowFunctionRector::class);
    $rector->rule(ArraySpreadInsteadOfArrayMergeRector::class);

    // Php80
    $rector->rule(MixedTypeRector::class);
    $rector->rule(ClassOnObjectRector::class);
    $rector->rule(ClassOnThisVariableObjectRector::class);
    $rector->rule(ClassPropertyAssignToConstructorPromotionRector::class);

    // Php81
    $rector->rule(FinalizePublicClassConstantRector::class);
    $rector->rule(FirstClassCallableRector::class);
    $rector->rule(ReadOnlyPropertyRector::class);

    // Php82
    $rector->rule(ReadOnlyClassRector::class);

    // Code Quality
    $rector->rule(SimplifyEmptyCheckOnEmptyArrayRector::class);
    $rector->rule(CallableThisArrayToAnonymousFunctionRector::class);

    // TypeDeclaration
    $rector->rule(AddParamTypeBasedOnPHPUnitDataProviderRector::class);
    $rector->rule(AddParamTypeFromPropertyTypeRector::class);
    $rector->rule(AddParamTypeSplFixedArrayRector::class);
    $rector->rule(ReturnTypeFromStrictConstantReturnRector::class);
    $rector->rule(ReturnTypeFromStrictTypedCallRector::class);
    $rector->rule(ReturnTypeFromReturnDirectArrayRector::class);
    $rector->rule(AddReturnTypeDeclarationFromYieldsRector::class);

    // Dead Code
    $rector->rule(RemoveUselessParamTagRector::class);
    $rector->rule(RemoveUselessReturnTagRector::class);
    $rector->rule(RemoveUselessVarTagRector::class);
    $rector->rule(RemoveUnusedVariableAssignRector::class);

    // Privatization
    $rector->rule(FinalizeClassesWithoutChildrenRector::class);
};
