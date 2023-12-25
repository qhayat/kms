# KMS Bundle
The **KMS Bundle** is a Symfony bundle designed for flexible content management, offering features for creating and managing pages and posts.

## Installation
To install this bundle, execute the following command via Composer:
```bash
composer require qamar/kms
```

## Configuration des routes
Add the following route configurations to your Symfony project's routes.yaml file to integrate the bundle's functionalities:
```yaml
_kms_bundle:
    resource: '@KmsBundle/config/routes.yaml'

# Only if you want to expose the API
_kms_bundle_api:
    resource: '@KmsBundle/config/api_routes.yaml'
```

## Contribution
Contributions are welcome! If you'd like to contribute to this project, please submit issues or pull requests on the bundle's official GitHub repository.
