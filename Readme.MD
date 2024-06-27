# Running the project

> In order to run the project, you need to have docker installed on your machine

- Run the command below while being positioned in the root folder: `make up`

- If everything is ok, the frontend will be available at port `5173` and the API on the port `9001`

## Creating a fake questionnaire

- In the root folder, run the command `make create-fake`

# Executing the tests

- Run `make test`

# Requirements

One of the challenges customers face when purchasing products from Manual, is that they are not sure which product is the most suitable for their condition.

To address this, we want to extend the functionality of our medical history questionnaire to also recommend appropriate products to the customer based on their answers.

The flow will be that the customer completes a questionnaire on the website and then they will be shown a list of products that has been specifically personalized for them. They can then choose from that list which product they want to order.

You are tasked with architecting the backend-side of this functionality for the Erectile Dysfunction (ED) category. The category currently contains the following products:
Sildenafil 50mg
Sildenafil 100mg
Tadalafil 10mg
Tadalafil 20mg

The questionnaire and related logic/restrictions question are provided in this spreadsheet.

Feel free to use an existing framework or bundle that you’re familiar with (like Symfony, Laravel, etc.). We don’t expect you to build this from scratch.

You are asked to provide the following:

- An API that returns the questionnaire, which includes all the questions and answers (plus any conditional restrictions for when questions should appear). Frontend engineers will use this API to render the complete questionnaire flow to the customer.
- An API that accepts the questionnaire answers and returns an array of the recommended products that the customer can purchase.
- A simple admin panel for the medical team to be able to input additional questions or alter the existing questions/recommendation logic.

Please assume that the product info is stored elsewhere, and for the purposes of this assignment you can use a simple identifier (e.g. sildenafil_50, tadalafil_10, etc). There are no specific requirements for the admin UI, you may implement as is most convenient for you.
