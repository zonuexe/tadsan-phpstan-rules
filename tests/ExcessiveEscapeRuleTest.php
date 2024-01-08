<?php

declare(strict_types=1);

namespace zonuexe\PHPStan;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @extends RuleTestCase<ExcessiveEscapeRule>
 */
#[CoversClass(ExcessiveEscapeRule::class)]
class ExcessiveEscapeRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ExcessiveEscapeRule();
    }

    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/data/exessive-escape-strings.php'], [
            [
                'シングルクォート内の\'\n\'は改行(LF)に展開されません。エスケープせずに書くか、""を使ってください。',
                5,
            ],
            [
                'シングルクォート内の\'\t\'は水平タブ(HT)に展開されません。エスケープせずに書くか、""を使ってください。',
                5,
            ],
            [
                'NowDoc内の\'\n\'は改行(LF)に展開されません。エスケープせずに書くか、""を使ってください。',
                9,
            ],
            [
                'NowDoc内の\'\t\'は水平タブ(HT)に展開されません。エスケープせずに書くか、""を使ってください。',
                9,
            ],
        ]);
    }
}
