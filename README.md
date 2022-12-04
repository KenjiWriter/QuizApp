
# Quiz app

A app for doing and creating new quizzes


## Badges


[![GPLv3 License](https://img.shields.io/badge/License-GPL%20v3-yellow.svg)](https://opensource.org/licenses/)


## Features

- Login/register
- Random genereted quizes
- Creating new quizes


## Tech Stack

**Frontend:** blade, TailwindCSS

**backend:** PHP, Laravel


## Roadmap

- Additional login with socialmedia

- Add profiles
- Add achievements
- Add editing quizes
- Add liking and commenting quizzes
- Sharing scores


## Run Locally

Clone the project

```bash
    git clone https://github.com/KenjiWriter/QuizApp.git
```

Run your locally database and create new one called "quizapp", after this open bash


```bash
    cd my-project
    php artisan migrate
```

Generate new env file


```bash
    cp .env.example .env
    php artisan key:generate
```

Run server

```bash
    php artisan serve
```

