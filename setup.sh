#!/bin/bash
# Set defaults
gcloud config set project compgcp-blog-demo
gcloud config set compute/region europe-west3
gcloud config set compute/zone europe-west3-a
# Create Datastore database with GCP console
# Set up Pub/Sub topics
gcloud pubsub topics create new-blogpost
gcloud pubsub topics create new-subscriber
# Create secret with Sendgrid API key
gcloud services enable secretmanager.googleapis.com
echo -n $SENDGRID_API_KEY | gcloud secrets create sendgrid-api-key --replication-policy="automatic" --data-file=-
# Deploy Cloud Functions
gcloud services enable cloudfunctions.googleapis.com
pushd cloud-functions/new-blogpost
gcloud functions deploy new-blogpost --region=europe-west3 --runtime nodejs10 --trigger-topic=new-blogpost --entry-point=newBlogpost
popd
pushd cloud-functions/new-subscriber
gcloud functions deploy new-subscriber --region=europe-west3 --runtime nodejs10 --trigger-topic=new-subscriber --entry-point=newSubscriber
popd
# Deploy website with Cloud Run
pushd blog-website

popd
# Add permission 'secretmanager.versions.access' to App Engine default service account