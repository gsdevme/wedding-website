# http://ceilidhandgavin.com/

[![Build Status](https://travis-ci.com/gsdevme/wedding-website.svg?branch=master)](https://travis-ci.com/gsdevme/wedding-website)

# Deployments

| Env | Description |
| --- | ----------- |
| Dev | docker-compose is used for level development |
| Prod | Deployed on kubernetes via gitops [here](https://github.com/gsdevme/homelab/tree/master/kubernetes-cluster/ovh-prod/wedding) |


# Usage

```
# start all the required containers
make start

# runs a composer install inside the container
make install

# setup the database
make init-db

# access the php containers shell
make shell

# run the CI (inside the shell)
make run-ci

# run the CI (outside the shell) _slower_
make ci
```

