#Jobs
## Setup
- `docker-compose up -d`
- `docker run --rm --interactive --tty --volume $PWD/jobs:/app --volume $COMPOSER_HOME:/tmp composer install`
- `docker-compose exec php bin/console doctrine:migrations:migrate`
#Tests
`docker-compose exec php vendor/bin/phpunit`
## Documentation
- In `docs/job_actions.postman_collection.json` there are api calls examples that can be imported by Postman
- In `docs/swagger.yml` there is the API documentation