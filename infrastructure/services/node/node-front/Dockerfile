FROM node:21-alpine
# Possible perfomance improve to mac -> FROM arm64v8/node:18-alpine

ENV NODE_OPTIONS=--openssl-legacy-provider

RUN npm install -g pnpm
RUN npm install -g vite


RUN apk update \
    && apk add git \
    && apk add nano bash python3 make

VOLUME ["/usr/src/app"]

WORKDIR "/usr/src/app"