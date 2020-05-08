<?php

use Illuminate\Database\Seeder;

class DeputiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        \App\Deputy::create([
            'lastName' => "Куксенко",

            'firstName' => "Марія",
            'middleName' => "Михайлівна",
            'party' => "\"Наш край\"",

            //      'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->save();
        \App\Deputy::create([
            'lastName' => "Пека",
            'firstName' => "Тетяна",
            'middleName' => "Миколаївна",
            'party' => "\"Опозиційна платформа - За життя\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Оліфер",
            'firstName' => "Віталій",

            'middleName' => "Миколайович",
            'party' => "\"Громадянська позиція\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Кучма",
            'firstName' => "Роман",

            'middleName' => "Михайлович",
            'party' => "\"Громадянська позиція\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Грузин",
            'firstName' => "Ніна",
            'middleName' => "Євгенівна",
            'party' => "\"Опозиційна платформа - За життя\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Пришедько",
            'firstName' => "Дмитро",
            'middleName' => "Олексійович",
            'party' => "Всеукраїнське об'єднання \"Батьківщина\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Говоруха",
            'firstName' => "Владислав",
            'middleName' => "Анатолійович",
            'party' => "\"Громадянська позиція\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Стешенко",
            'firstName' => "Євгенія",
            'middleName' => "Вячеславівна",
            'party' => "\"Європейська солідарність\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Коваль",
            'firstName' => "Олександр",
            'middleName' => "Олександрович",
            'party' => "\"Опозиційний блок\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Резворович",
            'firstName' => "Владислав",
            'middleName' => "Олегович",
            'party' => "Всеукраїнське об'єднання \"Батьківщина\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Коваль",
            'firstName' => "Олександр",
            'middleName' => "Прокофійович",
            'party' => "Всеукраїнське об'єднання \"Батьківщина\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Киричок",
            'firstName' => "Сергій",
            'middleName' => "Володимирович",
            'party' => "\"Радикальна партія Ляшка\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Слуцька",
            'firstName' => "Тетяна",
            'middleName' => "Василівна",
            'party' => "\"Радикальна партія Ляшка\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Слуцький",
            'firstName' => "Віталій",
            'middleName' => "Іванович",
            'party' => "\"Радикальна партія Ляшка\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Рябець",
            'firstName' => "Марина",
            'middleName' => "Анатоліївна",
            'party' => "",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Слюсарчук",
            'firstName' => "Олег",
            'middleName' => "Анатолійович",
            'party' => "\"Слуга народу\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Зібарєв",
            'firstName' => "Володимир",
            'middleName' => "Юрійович",
            'party' => "\"Опозиційний блок\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Семенюк",
            'firstName' => "Микола",
            'middleName' => "Олександрович",
            'party' => "Всеукраїнське об'єднання \"Батьківщина\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Моргун",
            'firstName' => "Віталій",
            'middleName' => "Юрійович",
            'party' => "\"Громадянська позиція\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Ткаченко",
            'firstName' => "Олена",
            'middleName' => "Миколаївна",
            'party' => "\"Європейська солідарність\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Расков",
            'firstName' => "Матвій",
            'middleName' => "Сергійович",
            'party' => "\"Слуга народу\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Теслюк",
            'firstName' => "Ігор",
            'middleName' => "Миколайович",
            'party' => "\"Наш край\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Маласай",
            'firstName' => "Віталій",
            'middleName' => "Миколайович",
            'party' => "\"Наш край\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Драган",
            'firstName' => "Юрій",
            'middleName' => "Миколайович",
            'party' => "\"Наш край\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Журавльов",
            'firstName' => "Анатолій",
            'middleName' => "Віталійович",
            'party' => "\"Опозиційний блок\"",

        ])->save();
        \App\Deputy::create([
            'lastName' => "Касьянов",
            'firstName' => "Нікіта",
            'middleName' => "Сергійович",
            'party' => "\"Слуга народу\"",

        ])->save();


    }
}
