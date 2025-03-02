<?php

namespace yahya077\FlutterpingPay\Schema;

/**
 * @property bool $with_selected_card
 * @property string $card_number
 * @property string $card_holder_name
 * @property string $card_expire_month
 * @property string $card_expire_year
 * @property string $card_cvc
 * @property string $card_alias
 * @property string $selected_card
 * @property string $note
 * @property string $ip
 * @property string $environment
 * @property array $extra
 */
class CompletePaymentParameters
{
    public bool $with_selected_card = false;

    public ?string $card_number;

    public ?string $card_holder_name;

    public ?string $card_expire_month;

    public ?string $card_expire_year;

    public ?string $card_cvc;

    public ?string $card_alias;

    public ?string $selected_card;

    public ?string $note;

    public string $ip;

    public string $environment;

    public array $extra;

    public static function fromArray(array $array): self
    {
        $instance = new self;
        $instance->with_selected_card = $array['with_selected_card'] ?? false;
        $instance->card_number = $array['card_number'] ?? null;
        $instance->card_holder_name = $array['card_holder_name'] ?? null;
        $instance->card_expire_month = $array['card_expire_month'] ?? null;
        $instance->card_expire_year = $array['card_expire_year'] ?? null;
        $instance->card_cvc = $array['card_cvc'] ?? null;
        $instance->card_alias = $array['card_alias'] ?? null;
        $instance->selected_card = $array['selected_card'] ?? null;
        $instance->note = $array['note'] ?? null;
        $instance->ip = $array['ip'];
        $instance->environment = $array['environment'];
        $instance->extra = $array['extra'] ?? [];

        return $instance;
    }

    public function toArray(): array
    {
        return [
            'with_selected_card' => $this->with_selected_card,
            'card_number' => $this->card_number,
            'card_holder_name' => $this->card_holder_name,
            'card_expire_month' => $this->card_expire_month,
            'card_expire_year' => $this->card_expire_year,
            'card_cvc' => $this->card_cvc,
            'card_alias' => $this->card_alias,
            'selected_card' => $this->selected_card,
            'note' => $this->note,
            'ip' => $this->ip,
            'environment' => $this->environment,
            'extra' => $this->extra,
        ];
    }

    public function toCollection(): \Illuminate\Support\Collection
    {
        return collect($this->toArray());
    }
}
