<?php

namespace Shika\Security;

enum Role : int
{
    case User = 0;
    case Manager = 1;
    case Admin = 2;
}