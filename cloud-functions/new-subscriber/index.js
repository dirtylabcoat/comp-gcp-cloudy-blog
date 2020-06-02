const { SecretManagerServiceClient } = require('@google-cloud/secret-manager');
const sendgridClient = require('@sendgrid/mail');
const secretsClient = new SecretManagerServiceClient();


async function getSendgridApiKey() {
    const [accessResponse] = await secretsClient.accessSecretVersion({
        name: 'projects/495893863869/secrets/sendgrid-api-key/versions/latest'
    });
    const sendgridApiKey = accessResponse.payload.data.toString('utf8');
    return sendgridApiKey;
}

// Triggered by Pub/Sub-topic "new-subscriber"
exports.newSubscriber = (event, context) => {
    // Get message data
    const msg = JSON.parse(Buffer.from(event.data, 'base64').toString());
    const emailAddress = msg.emailAddress;
    console.log(`New subscriber ${emailAddress} added`);
    // Get the Sendgrid API key from Google Secrets Manager and then
    // send e-mail to the new subscriber
    getSendgridApiKey()
        .then(apiKey => {
            sendgridClient.setApiKey(apiKey);
            const email = {
                to: emailAddress,
                from: 'cloudymccloud0001@gmail.com',
                subject: 'Welcome to the Cloudy Blog',
                text: `You are our newest subscriber. Enjoy!`,
                html: `You are our newest subscriber. <strong>Enjoy!</strong>`
            };
            sendgridClient.send(email);
            console.log("E-mail to new subscriber might have been sent");
        });
};
