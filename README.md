# Setup

```
sudo vim /etc/exports

# add 
/Users/gavin/Sites -alldirs -mapall=502:20 localhost

sudo vim /etc/nfs.conf
# add
nfs.server.mount.require_resv_port = 0

# restart nfsd
sudo nfsd restart
```

# Usage

```
docker-compose -f infrastructure/docker-compose.yml \
    -f infrastructure/docker-compose.dev.yml \
    --project-directory $(pwd) build
```
