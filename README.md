# Project Rodh'Raj

## Setup database

Setup the database skeleton:

```bash
php php bin/console d:s:u --dump-sql -f
```

## Testing

Setup basic admin user :

```bash
php bin/console sylius:fixtures:load -v
```

- Name : admin
- Password : p@ssw0rd

## Setup data
in .env set variable: ROOMS_DATA_YAML_LINK (link to the file rooms_data.yaml on your computer)

example: ROOMS_DATA_YAML_LINK=C:\xampp\htdocs\rodh_raj\private\rooms_data.yaml


Setup vanilla rooms in database :

```bash
php bin/console app:vanilla-rooms-warmup
```
