# SAdle
## Setting up the production environment
## Setting up the development environment
1. Clone the repository

    `$ git clone https://github.com/milinkovicmilos/SAdle.git`
   
   Or if using SSH:
   
    `$ git clone git@github.com:milinkovicmilos/SAdle.git`

2. Navigate to app directory

    `$ cd SAdle`

3. Build docker images and run containers using docker compose

    `$ docker compose up -d`

4. Run composer install within webserver container

    Start a shell inside webserver container:
   
   `$ docker exec -it webserver /bin/bash`

    Run:

   `# composer install`

5. Run through setup within mariadb container

    Start a shell inside mariadb container:
   
   `$ docker exec -it mariadb /bin/bash`

    Navigate to root dir:

   `$ cd`

    Run through setup script:

   `# /bin/bash setup.sh`

   Note: Env variables setup is not neccessary and can be skipped as we will modify them later from outside the container

   Close the shell:

   `# exit`
   
6. Populate the database

7. Add starting games to the table

   Start a shell inside mariadb container:
   
   `$ docker exec -it mariadb /bin/bash`

   Navigate to root dir:

   `$ cd`

    Run the script to add initial games:

   `# mariadb -u root sadle_db < add_starting_games.sql`
   
   Close the shell:

   `# exit`

8. Setup the env variables

     Copy over the example env
   
      `$ cp app/Config/Envs/.env.template app/Config/Envs/.env`

     Edit the env file
   
     Note: Use mariadb as DB Hostname (HOST=mariadb) and root as DB Username unless you setup compose.yaml differently

9. The app is available on `localhost:8080`
