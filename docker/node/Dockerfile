FROM node:16.3.0-alpine
WORKDIR "/application/client"

# Add `node_modules/.bin` to $PATH
ENV PATH /application/client/node_modules/.bin:$PATH

# Add application
COPY ./client/ ./

# Install dependencies
RUN npm ci --silent

# Start dev server
CMD ["npm", "start"]
