# Build stage
FROM mcr.microsoft.com/dotnet/sdk:10.0 AS build

WORKDIR /app

# Copy the project file
COPY csharp/BrasilBurger.Web/*.csproj ./

# Restore dependencies
RUN dotnet restore

# Copy the rest of the source code
COPY csharp/BrasilBurger.Web/ ./

# Build and publish
RUN dotnet publish -c Release -o /app/publish

# Runtime stage
FROM mcr.microsoft.com/dotnet/aspnet:10.0

WORKDIR /app

# Copy the published application
COPY --from=build /app/publish .

# Expose the port
EXPOSE 8080

# Set environment variables
ENV ASPNETCORE_URLS=http://+:8080
ENV ASPNETCORE_ENVIRONMENT=Production

# Run the application
ENTRYPOINT ["dotnet", "BrasilBurger.Web.dll"]
