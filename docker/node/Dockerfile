FROM node:16.3.0-alpine
WORKDIR "/application/client"

# Add `node_modules/.bin` to $PATH
ENV PATH /application/client/node_modules/.bin:$PATH

RUN apk update && apk upgrade && \
    apk add --no-cache git
    # apk add --no-cache bash git openssh

# Add application
COPY ./client/ ./

# Install dependencies
RUN npm ci --silent

# Start dev server
CMD ["npm", "start"]
