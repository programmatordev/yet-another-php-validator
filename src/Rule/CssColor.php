<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\CssColorException;
use ProgrammatorDev\Validator\Exception\UnexpectedOptionException;
use ProgrammatorDev\Validator\Exception\UnexpectedTypeException;

class CssColor extends AbstractRule implements RuleInterface
{
    public const FORMAT_HEX_LONG = 'hex-long';
    public const FORMAT_HEX_LONG_WITH_ALPHA = 'hex-long-with-alpha';
    public const FORMAT_HEX_SHORT = 'hex-short';
    public const FORMAT_HEX_SHORT_WITH_ALPHA = 'hex-short-with-alpha';
    public const FORMAT_BASIC_NAMED_COLORS = 'basic-named-colors';
    public const FORMAT_EXTENDED_NAMED_COLORS = 'extended-named-colors';
    public const FORMAT_SYSTEM_COLORS = 'system-colors';
    public const FORMAT_KEYWORDS = 'keywords';
    public const FORMAT_RGB = 'rgb';
    public const FORMAT_RGBA = 'rgba';
    public const FORMAT_HSL = 'hsl';
    public const FORMAT_HSLA = 'hsla';

    private const COLOR_FORMATS = [
        self::FORMAT_HEX_LONG,
        self::FORMAT_HEX_LONG_WITH_ALPHA,
        self::FORMAT_HEX_SHORT,
        self::FORMAT_HEX_SHORT_WITH_ALPHA,
        self::FORMAT_BASIC_NAMED_COLORS,
        self::FORMAT_EXTENDED_NAMED_COLORS,
        self::FORMAT_SYSTEM_COLORS,
        self::FORMAT_KEYWORDS,
        self::FORMAT_RGB,
        self::FORMAT_RGBA,
        self::FORMAT_HSL,
        self::FORMAT_HSLA
    ];

    private const PATTERN_HEX_LONG = '/^#[0-9a-f]{6}$/i';
    private const PATTERN_HEX_LONG_WITH_ALPHA = '/^#[0-9a-f]{8}$/i';
    private const PATTERN_HEX_SHORT = '/^#[0-9a-f]{3}$/i';
    private const PATTERN_HEX_SHORT_WITH_ALPHA = '/^#[0-9a-f]{4}$/i';
    // https://www.w3.org/wiki/CSS/Properties/color/keywords#Basic_Colors
    private const PATTERN_BASIC_NAMED_COLORS = '/^(black|silver|gray|white|maroon|red|purple|fuchsia|green|lime|olive|yellow|navy|blue|teal|aqua)$/i';
    // https://www.w3.org/wiki/CSS/Properties/color/keywords#Extended_colors
    private const PATTERN_EXTENDED_NAMED_COLORS = '/^(aliceblue|antiquewhite|aqua|aquamarine|azure|beige|bisque|black|blanchedalmond|blue|blueviolet|brown|burlywood|cadetblue|chartreuse|chocolate|coral|cornflowerblue|cornsilk|crimson|cyan|darkblue|darkcyan|darkgoldenrod|darkgray|darkgreen|darkgrey|darkkhaki|darkmagenta|darkolivegreen|darkorange|darkorchid|darkred|darksalmon|darkseagreen|darkslateblue|darkslategray|darkslategrey|darkturquoise|darkviolet|deeppink|deepskyblue|dimgray|dimgrey|dodgerblue|firebrick|floralwhite|forestgreen|fuchsia|gainsboro|ghostwhite|gold|goldenrod|gray|green|greenyellow|grey|honeydew|hotpink|indianred|indigo|ivory|khaki|lavender|lavenderblush|lawngreen|lemonchiffon|lightblue|lightcoral|lightcyan|lightgoldenrodyellow|lightgray|lightgreen|lightgrey|lightpink|lightsalmon|lightseagreen|lightskyblue|lightslategray|lightslategrey|lightsteelblue|lightyellow|lime|limegreen|linen|magenta|maroon|mediumaquamarine|mediumblue|mediumorchid|mediumpurple|mediumseagreen|mediumslateblue|mediumspringgreen|mediumturquoise|mediumvioletred|midnightblue|mintcream|mistyrose|moccasin|navajowhite|navy|oldlace|olive|olivedrab|orange|orangered|orchid|palegoldenrod|palegreen|paleturquoise|palevioletred|papayawhip|peachpuff|peru|pink|plum|powderblue|purple|red|rosybrown|royalblue|saddlebrown|salmon|sandybrown|seagreen|seashell|sienna|silver|skyblue|slateblue|slategray|slategrey|snow|springgreen|steelblue|tan|teal|thistle|tomato|turquoise|violet|wheat|white|whitesmoke|yellow|yellowgreen)$/i';
    // https://drafts.csswg.org/css-color/#css-system-colors
    private const PATTERN_SYSTEM_COLORS = '/^(AccentColor|AccentColorText|ActiveText|ButtonBorder|ButtonFace|ButtonText|Canvas|CanvasText|Field|FieldText|GrayText|Highlight|HighlightText|LinkText|Mark|MarkText|SelectedItem|SelectedItemText|VisitedText)$/i';
    // https://drafts.csswg.org/css-color/#transparent-color
    // https://drafts.csswg.org/css-color/#currentcolor-color
    private const PATTERN_KEYWORDS = '/^(transparent|currentColor)$/i';
    private const PATTERN_RGB = '/^rgb\(\s*(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),\s*(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),\s*(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d)\s*\)$/i';
    private const PATTERN_RGBA = '/^rgba\(\s*(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),\s*(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),\s*(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d),\s*(0|0?\.\d+|1(\.0)?)\s*\)$/i';
    private const PATTERN_HSL = '/^hsl\(\s*(0|360|35\d|3[0-4]\d|[12]\d\d|0?\d?\d),\s*(0|100|\d{1,2})%,\s*(0|100|\d{1,2})%\s*\)$/i';
    private const PATTERN_HSLA = '/^hsla\(\s*(0|360|35\d|3[0-4]\d|[12]\d\d|0?\d?\d),\s*(0|100|\d{1,2})%,\s*(0|100|\d{1,2})%,\s*(0|0?\.\d+|1(\.0)?)\s*\)$/i';

    private const COLOR_PATTERNS = [
        self::FORMAT_HEX_LONG => self::PATTERN_HEX_LONG,
        self::FORMAT_HEX_LONG_WITH_ALPHA => self::PATTERN_HEX_LONG_WITH_ALPHA,
        self::FORMAT_HEX_SHORT => self::PATTERN_HEX_SHORT,
        self::FORMAT_HEX_SHORT_WITH_ALPHA => self::PATTERN_HEX_SHORT_WITH_ALPHA,
        self::FORMAT_BASIC_NAMED_COLORS => self::PATTERN_BASIC_NAMED_COLORS,
        self::FORMAT_EXTENDED_NAMED_COLORS => self::PATTERN_EXTENDED_NAMED_COLORS,
        self::FORMAT_SYSTEM_COLORS => self::PATTERN_SYSTEM_COLORS,
        self::FORMAT_KEYWORDS => self::PATTERN_KEYWORDS,
        self::FORMAT_RGB => self::PATTERN_RGB,
        self::FORMAT_RGBA => self::PATTERN_RGBA,
        self::FORMAT_HSL => self::PATTERN_HSL,
        self::FORMAT_HSLA => self::PATTERN_HSLA
    ];

    private array $formats;
    private string $message = 'The {{ name }} value is not a valid CSS color.';

    public function __construct(
        ?array $formats = null,
        ?string $message = null
    )
    {
        $this->formats = $formats ?? self::COLOR_FORMATS;
        $this->message = $message ?? $this->message;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        foreach ($this->formats as $format) {
            if (!\in_array($format, self::COLOR_FORMATS, true)) {
                throw new UnexpectedOptionException('format', self::COLOR_FORMATS, $format);
            }
        }

        if (!\is_string($value)) {
            throw new UnexpectedTypeException('string', get_debug_type($value));
        }

        foreach ($this->formats as $format) {
            $pattern = self::COLOR_PATTERNS[$format];

            // it is valid if at least one pattern matches
            if (\preg_match($pattern, $value)) {
                return;
            }
        }

        throw new CssColorException(
            message: $this->message,
            parameters: [
                'value' => $value,
                'name' => $name,
                'formats' => $this->formats
            ]
        );
    }
}