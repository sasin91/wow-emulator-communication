<?php 

namespace Sasin91\WoWEmulatorCommunication\Commands\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Sasin91\WoWEmulatorCommunication\Concerns\UsesContainer;
use Sasin91\WoWEmulatorCommunication\EmulatorManager;

trait Validatable
{
    /**
     * Array of validation rules for given parameters.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Run validation of parameters.
     *
     * @throws \Illuminate\Validation\ValidationException
     * @return void
     */
    public function validate()
    {
        Validator::validate($this->parameters(), $this->rules());
    }

    /**
     * Get the current rules,
     * optionally override them.
     *
     * @return array
     */
    public function rules()
    {
        if (func_num_args() > 0) {
            $this->rules = Arr::wrap(...func_get_args());
        }

        return $this->rules;
    }
}
