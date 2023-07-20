<?php

namespace Database\Factories;

use App\Models\UploadFile;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class UploadFileFactory extends Factory
{
    protected $model = UploadFile::class;
    protected $faker = Faker::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            UploadFile::FILE_NAME => $this->faker->word().$this->faker->fileExtension(),
            UploadFile::FILE_EXTENSION => $this->faker->fileExtension(),
            UploadFile::FILE_PATH => $this->faker->filePath(),
            UploadFile::FILE_SIZE => $this->faker->numberBetween(1000, 4000),
        ];
    }
}
