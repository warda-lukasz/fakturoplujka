<?php

namespace Enums;

enum Color: string
{
    case Default = "\33[39m";
    case Black = "\33[30m";
    case Red = "\33[31m";
    case Green = "\33[32m";
    case Yellow = "\33[33m";
    case Blue = "\33[34m";
    case Magenta = "\33[35m";
    case Cyan = "\33[36m";
    case LightGray = "\33[37m";
    case DarkGray = "\33[90m";
    case LightRed = "\33[91m";
    case LightGreen = "\33[92m";
    case LightYellow = "\33[93m";
    case LightBlue = "\33[94m";
    case LightMagenta = "\33[95m";
    case LightCyan = "\33[96m";
    case White = "\33[97m";
}
