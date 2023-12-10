# quizGPT
:warning: Most of the project is generated using GPT4

**QuizGPT: A Customizable Quiz Generation Platform**

## Overview:
QuizGPT is an innovative web application designed to create and participate in quizzes for exam preparation and knowledge testing. Utilizing the power of OpenAI's GPT API, QuizGPT allows users to generate quizzes on specific subjects, offering a dynamic and interactive learning experience.

## Features:

- Quiz Selection:
  Users can browse and select from a list of available quizzes on various subjects.
- Interactive Quiz Experience:
  Each quiz presents randomly selected multiple-choice questions. Users receive immediate feedback on their answers with a score and visual indications of correct and incorrect responses.
- Quiz Generation:
  Verified users can generate new quizzes by specifying a title, subject, and theme. The OpenAI GPT API is used to create relevant questions and answers.
- User Verification:
  Enhanced security through YubiKey integration and API key registration for verified users.
- Target Audience:
  Students, educators, and anyone interested in testing their knowledge or preparing for exams.

##Technologies Used:

Backend: Python with Flask or Django Framework (depending on your preference for simplicity or scalability).
Frontend: JavaScript, HTML, CSS (React or Vue.js for a more dynamic frontend).
Database: PostgreSQL or MySQL.
API Integration: OpenAI GPT for quiz question generation.
Authentication: Integration of YubiKey for secure user verification.
Security Measures: Secure handling of API keys, HTTPS connections, and safe storage of user data.
