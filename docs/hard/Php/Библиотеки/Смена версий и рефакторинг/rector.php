<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector;
use Rector\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodeQuality\Rector\Switch_\SingularSwitchToIfRector;
use Rector\CodingStyle\Rector\ClassMethod\NewlineBeforeNewAssignSetRector;
use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\CodingStyle\Rector\PostInc\PostIncDecToPreIncDecRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector;
use Rector\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector;
use Rector\Naming\Rector\ClassMethod\RenameVariableToMatchNewTypeRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/qrInformer/instruments/EmailSender.php',
    ]);
    $rectorConfig->skip([
        __DIR__ . '/qrInformer/db',
    ]);
    $rectorConfig->skip([
        RenameVariableToMatchNewTypeRector::class => [
            __DIR__ . '/qrInformer/entities/admin/authorization/role/RoleController.php',
            __DIR__ . '/qrInformer/entities/admin/authorization/roleGroup/RoleGroupService.php',
            __DIR__ . '/qrInformer/entities/admin/authorization/rightGroup/RightGroupService.php',

        ],
        RenameParamToMatchTypeRector::class       => [
            __DIR__ . '/qrInformer/entities/throwable',
        ],
    ]);

    $rectorConfig->skip([
        SimplifyUselessVariableRector::class,
        FlipTypeControlToUseExclusiveTypeRector::class,
        SingularSwitchToIfRector::class,
        LocallyCalledStaticMethodToNonStaticRector::class,
        AddOverrideAttributeToOverriddenMethodsRector::class,
        EncapsedStringsToSprintfRector::class,
        NewlineAfterStatementRector::class,
        NewlineBeforeNewAssignSetRector::class,
        PostIncDecToPreIncDecRector::class,
        RemoveUnusedPrivateMethodRector::class,
        ChangeAndIfToEarlyReturnRector::class,
        FinalizeClassesWithoutChildrenRector::class,
    ]);


    // register a single rule
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);

    // define sets of rules
    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        LevelSetList::UP_TO_PHP_83,
        SetList::TYPE_DECLARATION,
        SetList::CODING_STYLE,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::NAMING,
        SetList::PRIVATIZATION,
        //        SetList::STRICT_BOOLEANS,
    ]);
};
