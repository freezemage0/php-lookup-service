<?php


namespace Freezemage\LookupBot\Documentation;

interface Entry
{
    public function compile(Compiler $compiler): string;
}
