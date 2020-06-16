const { SecretManagerServiceClient } = require('@google-cloud/secret-manager');
const sendgridClient = require('@sendgrid/mail');
const secretsClient = new SecretManagerServiceClient();
const { Datastore } = require('@google-cloud/datastore');
const datastore = new Datastore();

async function getSendgridApiKey() {
    const [accessResponse] = await secretsClient.accessSecretVersion({
        name: 'projects/495893863869/secrets/sendgrid-api-key/versions/latest'
    });
    const sendgridApiKey = accessResponse.payload.data.toString('utf8');
    return sendgridApiKey;
}

async function sendEmailToSubscribers(subject) {
    const query = datastore.createQuery('blog', 'subscriber').order('created', { descending: false });
    const [subscribers] = await datastore.runQuery(query);
    for (const sub of subscribers) {
        const subEmail = sub['emailAddress'];
        console.log(`Subscriber ${subEmail}`);
        const email = {
            to: sub['emailAddress'],
            from: 'cloudymccloud0001@gmail.com',
            subject: 'New blog post',
            text: `Go read the new post, ${subject}.`,
            html: `Go read the new post, <strong>${subject}</strong>`
        };
        sendgridClient.send(email);
    }
}

// Triggered by Pub/Sub-topic "new-blogpost"
exports.newBlogpost = (event, context) => {
    // Get message data
    const msg = JSON.parse(Buffer.from(event.data, 'base64').toString());
    const subject = msg.subject;
    console.log(`New blog published with subject ${subject}`);
    // Get the Sendgrid API key from Google Secrets Manager and then
    // send e-mail to all the subscribers
    getSendgridApiKey()
        .then(apiKey => {
            sendgridClient.setApiKey(apiKey);
            sendEmailToSubscribers(subject);
            console.log("E-mail(s) about new blog post might have been sent");
        });
};
