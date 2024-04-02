# Timezone

Validates that a value is a valid timezone identifier.

```php
Timezone(
    string $timezoneGroup = \DateTimeZone::ALL,
    ?string $countryCode = null,
    ?string $message = null
);
```

## Basic Usage

```php
// all timezone identifiers
Validator::timezone()->validate('Europe/Lisbon'); // true

// restrict timezone identifiers to a specific geographical zone
Validator::timezone(timezoneGroup: \DateTimeZone::EUROPE)->validate('Europe/Lisbon'); // true
Validator::timezone(timezoneGroup: \DateTimeZone::AFRICA)->validate('Europe/Lisbon'); // false
// or multiple geographical zones
Validator::timezone(timezoneGroup: \DateTimeZone::AFRICA | \DateTimeZone::EUROPE)->validate('Europe/Lisbon'); // true

// restrict timezone identifiers to a specific country
Validator::timezone(timezoneGroup: \DateTimeZone::PER_COUNTRY, countryCode: 'pt')->validate('Europe/Lisbon'); // true
Validator::timezone(timezoneGroup: \DateTimeZone::PER_COUNTRY, countryCode: 'en')->validate('Europe/Lisbon'); // false
```

> [!NOTE]
> An `UnexpectedValueException` will be thrown when the `timezoneGroup` value is `\DateTimeZone::PER_COUNTRY`
> and the `countryCode` value is `null` (not provided).

> [!NOTE]
> An `UnexpectedValueException` will be thrown when the `countryCode` value is not valid.
> Only if the `timezoneGroup` value is `\DateTimeZone::PER_COUNTRY`, otherwise it is ignored.

## Options

### `timezoneGroup`

type: `int` default: `\DateTimeZone::ALL`

Set this option to restrict timezone identifiers to a specific geographical zone. 

Available timezone groups:

- `\DateTimeZone::AFRICA`
- `\DateTimeZone::AMERICA`
- `\DateTimeZone::ANTARCTICA`
- `\DateTimeZone::ARCTIC`
- `\DateTimeZone::ASIA`
- `\DateTimeZone::ATLANTIC`
- `\DateTimeZone::AUSTRALIA`
- `\DateTimeZone::EUROPE`
- `\DateTimeZone::INDIAN`
- `\DateTimeZone::PACIFIC`

In addition, there are special timezone groups:

- `\DateTimeZone::ALL` all timezones;
- `\DateTimeZone::ALL_WITH_BC` all timezones including deprecated timezones;
- `\DateTimeZone::PER_COUNTRY` timezones per country (must be used together with the [`countryCode`](#countrycode) option);
- `\DateTimeZone::UTC` UTC timezones.

### `countryCode`

type: `?string` default: `null`

If the `timezoneGroup` option value is `\DateTimeZone::PER_COUNTRY`, 
this option is required to restrict valid timezone identifiers to the ones that belong to the given country.

Must be a valid alpha-2 country code.
Check the [official country codes](https://en.wikipedia.org/wiki/ISO_3166-1#Current_codes) list for more information.

### `message`

type: `?string` default: `The {{ name }} value is not a valid timezone, {{ value }} given.`

Message that will be shown if the input value is not a valid timezone.

The following parameters are available:

| Parameter           | Description               |
|---------------------|---------------------------|
| `{{ value }}`       | The current invalid value |
| `{{ name }}`        | Name of the invalid value |
| `{{ countryCode }}` | Selected country code     |

## Changelog

- `0.3.0` Created
