# ceilidhandgavin.com

[![build](https://github.com/gsdevme/wedding-website/actions/workflows/build.yml/badge.svg)](https://github.com/gsdevme/wedding-website/actions/workflows/build.yml)

The wedding website for Ceilidh & Gavin, 12th June 2021. It is now a time
capsule: a single static page served by nginx, with no build step and no
runtime beyond the web server.

## Layout

| Path | Purpose |
| --- | --- |
| `site/` | Document root: `index.html`, `404.html` and `assets/` |
| `infrastructure/` | `Dockerfile`, `nginx.conf`, `docker-compose.yml`, `smoke-test.sh` |
| `legacy/` | The original Symfony application, kept for reference only |

## Running locally

```
make start   # builds the image and serves http://localhost:8080
make test    # smoke-tests the running container
make logs
make stop
```

`make push` builds and pushes `ghcr.io/gsdevme/wedding-website:prod` (log in
first with `docker login ghcr.io`). The GitHub Actions workflow does the same
on every push to `master` using the built-in `GITHUB_TOKEN`, and only builds
and smoke-tests on pull requests. The package must be public, or the cluster
needs an image pull secret for ghcr.io.

The image is `nginxinc/nginx-unprivileged`: it runs as a non-root user and
listens on port 8080. `/api/healthz` returns `{"success":"ok"}` for the
Kubernetes probes.

## Assets

The CSS, JS, images and fonts under `site/assets` were never committed with
the PHP app. They were recovered from the last published assets image,
`gsdevme/wedding-website-assets:prod` (2021), trimmed to the files the page
uses, and are now committed. `make assets-extract` re-extracts the full set
from that image if it is ever needed again.

## Deployment

Production runs on Kubernetes via GitOps in the
[homelab repo](https://github.com/gsdevme/homelab/tree/master/kubernetes-cluster/ovh-prod/wedding).

The image lives on GHCR, not Docker Hub, so the existing
`gsdevme/wedding-website:prod` PHP image is untouched until the manifests are
switched over. Changes needed in the homelab repo:

- Point the `wedding` Deployment at `ghcr.io/gsdevme/wedding-website:prod` with the
  container and Service on port 8080; drop the PHP-FPM, Redis and nginx
  sidecar containers and their config.
- Remove the separate assets Deployment and the `a.ceilidhandgavin.com`
  ingress host; assets are served from the same origin under `/assets`.
