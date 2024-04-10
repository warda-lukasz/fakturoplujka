<?php

namespace Enums;

enum Format: string
{
    case Bold = "\33[1m";
    case Dim = "\33[2m";
    case Underline = "\33[4m";
    case Blink = "\33[5m";
    case Reverse = "\33[7m";
    case Hidden = "\33[8m";
}
