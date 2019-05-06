# Project Rodh'Raj

For the documentation of Sylius testing/development, [see here](https://github.com/Sylius/PluginSkeleton)

## Setup

Setup vanilla rooms in database :

```bash
php bin/console app:vanilla-rooms-warmup
```

## Testing

Setup basic admin user :

```bash
php bin/console sylius:fixtures:load
```

- Name : admin
- Password : p@ssw0rd
