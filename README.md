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

Setup vanilla rooms in database :

```bash
php bin/console app:vanilla-rooms-warmup
```
