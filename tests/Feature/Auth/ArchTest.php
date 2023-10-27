<?php

test('globals')
    ->expect(['dd', 'ds', 'var_dump', 'ray', 'dump'])
    ->not->toBeUsed();
