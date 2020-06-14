FROM node:12

ENV PORT 3000

# Create app directory
RUN mkdir -p /app/client
WORKDIR /app/client

# Installing dependencies
RUN npm install

# Copying source files
COPY . /app/client

# Building app
EXPOSE 3000

# Running the app
CMD "npm" "run" "dev"