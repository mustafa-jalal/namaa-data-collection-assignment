<h1  align="center"> Namaa "Data Collection" Case Study </h1>  <br>



<p  align="center">

Further Enhancements :
- add caching layer.
- support data pagination
- Build a logging service.
- add more unit & integration tests coverage.
</p>


## Table of Contents

-  [Table of Contents](#table-of-contents)

-  [Introduction](#introduction)

-  [API Documentation](#api-documentation)

-  [Requirements](#requirements)

-  [Installation](#installation)


## Introduction:
Simple api that retrieve users payment transactions with functional filtering system

## API Documentation:
    * `GET api/v1/users`: to list all users payment transactions
    *  you can filter with spacific provider: `api/v1/users?provider=DataProviderX`
    *  api support many other filters: currency, balanceMin, balanceMax or statusCode
    * `api/v1/users?currency=USD&balanceMin=10&balanceMax=100&statusCode=authorised`


# Requirements:

### Local
*  PHP v8.2.10

### Docker
*  Docker version 24.0.6

# Installation:
## step 1:

Make sure docker installed and running then change directory to the project's root (where `dockerfile` is ) and run the following command which will build the images if the images **do not exist** and starts the containers.

When ready, run it:

```bash

$ sudo docker build -t namaa-app .
```

## step 2:
when 'namaa-app' image built and ready run the follow command to start a new container from this image

```bash

$ sudo docker run -p 8000:80 namaa-app

```
Application will run by default on port `8000`, and is accessible from `http://localhost:8080`



Configure the port in port binding if needed.
