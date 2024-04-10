<?php

namespace Enums;

enum FormatReset: string
{
    case All = "\33[0m";
    case Bold = "\33[21m";
    case Dim = "\33[22m";
    case Underline = "\33[24m";
    case Blink = "\33[25m";
    case Reverse = "\33[27m";
    case Hidden = "\33[28m";
}
