<?php

declare(strict_types=1);

namespace zonuexe\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use function in_array;
use function sprintf;
use function str_contains;

/**
 * @implements Rule<String_>
 */
class ExcessiveEscapeRule implements Rule
{
    private const EXCESSIVE_ESCAPE_CHARACTERS = [
        '\n' => '改行(LF)',
        '\r' => '改行(CR)',
        '\t' => '水平タブ(HT)',
        '\v' => '垂直タブ(VT)',
        '\e' => 'エスケープ(ESC)',
        '\f' => 'フォームフィード(FF)',
    ];

    public function getNodeType(): string
    {
        return String_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $stringKind = $node->getAttributes()['kind'] ?? null;
        if (!in_array($stringKind, [String_::KIND_SINGLE_QUOTED, String_::KIND_NOWDOC], true)) {
            return [];
        }

        $errors = [];
        foreach (self::EXCESSIVE_ESCAPE_CHARACTERS as $char => $description) {
            if (str_contains($node->value, $char)) {
                $errors[] = RuleErrorBuilder::message(sprintf(
                    '%s内の\'%s\'は%sに展開されません。エスケープせずに書くか、""を使ってください。',
                    match ($stringKind) {
                        String_::KIND_SINGLE_QUOTED => 'シングルクォート',
                        String_::KIND_NOWDOC => 'NowDoc',
                    },
                    $char,
                    $description,
                ))->build();
            }
        }

        return $errors;
    }
}
