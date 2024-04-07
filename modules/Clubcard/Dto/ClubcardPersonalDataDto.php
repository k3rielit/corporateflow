<?php

namespace Modules\Clubcard\Dto;

use Illuminate\Database\Eloquent\Factories\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;

class ClubcardPersonalDataDto
{
    protected string $birthdate = '2000-01-01';
    protected string $city = 'City';
    protected string $clubcardNumber;
    protected string $email;
    protected string $firstName = 'Among';
    protected string $gender = 'M';
    protected string $lastName = 'Us';
    protected bool $marketingConsent = false;
    protected string $street = 'Street';
    protected string $streetNumber = '1';
    protected string $zipcode = '1000';

    public function __construct(?string $clubcard, ?string $email)
    {
        $this->clubcardNumber = $clubcard ?? '';
        $this->email = $email ?? '';
    }

    public static function make(?string $clubcard, ?string $email): static
    {
        return new static($clubcard, $email);
    }

    /**
     * Generates realistic fake personal data.
     * @param string $locale Optional: Defines the Faker\Generator locale, see more at https://fakerphp.github.io/locales/hu_HU/
     * @return $this
     */
    public function fake(string $locale = 'hu_HU'): static
    {
        $faker = fake($locale);
        $this->birthdate = $faker->date('Y-m-d', '2000-01-01');
        $this->city = $faker->city();
        $this->firstName = $faker->firstNameMale();
        $this->lastName = $faker->firstNameMale();
        $this->street = $faker->streetName();
        $this->streetNumber = $faker->numberBetween(7, 156);
        $this->zipcode = $faker->postcode();
        return $this;
    }

    /**
     * Transforms the DTO into an array, matching the format expected by the API.
     * @return array
     */
    public function toArray(): array
    {
        return [
            'birthdate' => $this->birthdate,
            'city' => $this->city,
            'clubcard_number' => $this->clubcardNumber,
            'email' => $this->email,
            'first_name' => $this->firstName,
            'gender' => $this->gender,
            'last_name' => $this->lastName,
            'marketing_consent' => $this->marketingConsent,
            'street' => $this->street,
            'street_number' => $this->streetNumber,
            'zipcode' => $this->zipcode,
        ];
    }

    /**
     * Transforms the DTO into a JSON string, matching the format expected by the API.
     * @param int $flags Optional: json_encode flags
     * @param int $depth Optional: json_encode depth
     * @return string
     */
    public function toJson(int $flags = 0, int $depth = 512): string
    {
        return json_encode($this->toArray(), $flags, $depth);
    }

}
