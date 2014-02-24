<?php

namespace TweedeGolf\Generator\Console\Input;

use Symfony\Component\Console\Formatter\OutputFormatter;
use TweedeGolf\Generator\Console\Questioner;
use TweedeGolf\Generator\Util\InputFieldHelper;

abstract class AbstractInputType implements InputTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return [
            'description' => null,
            'description_width' => 80,
            'prompt' => null,
            'prompt_append' => ':',
        ];
    }

    protected function getPrompt(array $options)
    {
        $prompt = $options['prompt'];
        if ($prompt === null) {
            $prompt = InputFieldHelper::getTitleForFieldname($options['property']);
        }

        $default = $this->getPromptDefault($options);
        if ($default) {
            $default = " <fg=cyan>[{$default}]</fg=cyan>";
        }

        $append = $options['prompt_append'];
        return "<question>{$prompt}{$append}</question>{$default} ";
    }

    protected function preamble(array $options, Questioner $questioner)
    {
        $questioner->getOutput()->writeln("");
        if ($options['description'] !== null) {
            $description = $options['description'];
            $questioner->message($description, $options['description_width']);
        }
    }

    public function getPromptDefault(array $options)
    {
        return $options['default'];
    }
}
