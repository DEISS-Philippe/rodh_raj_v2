# Project Rodh'Raj

## Setup database

Setup the database skeleton:

```bash
php php bin/console d:s:u --dump-sql -f
```

## Setup data
in .env set variable: ROOMS_DATA_YAML_LINK (link to the file rooms_data.yaml on your computer)

example: ROOMS_DATA_YAML_LINK=C:\xampp\htdocs\rodh_raj\private\rooms_data.yaml


Setup vanilla rooms in database :

```bash
php bin/console app:vanilla-rooms-warmup -v
```

## Testing

Setup basic admin user (rooms need to be present in DB before executing this command):

```bash
php bin/console sylius:fixtures:load
```

- Name : admin
- Password : p@ssw0rd
