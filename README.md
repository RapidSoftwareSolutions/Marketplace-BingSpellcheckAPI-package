# BingSpellcheckAPI Package
Bing Spell Check API performs contextual spell checking for any text and provides inline suggestions for misspelled words.
* Domain: microsoft.com
* Credentials: subscriptionKey

## How to get credentials: 
 1. Go to the [Service page](https://www.microsoft.com/cognitive-services/en-us/computer-vision-api)
 2. Create [Microsoft account](https://www.microsoft.com/cognitive-services/en-US/subscriptions) or log in. 
 3. Choose "Bing Spell Check - Preview" to create new subscription
 4. In **Key** section choose Key1 or Key2 and press <kbd>Show</kbd> or  <kbd>Copy</kbd>

## TOC: 
* [getSpellCheck](#getSpellCheck)
 
<a name="getSpellCheck"/>
## BingSpellcheckAPI.getSpellCheck
Provides a text spell check for spelling and grammar errors.

| Field          | Type       | Description
|----------------|------------|----------
| subscriptionKey| credentials| Required: The api key obtained from Microsoft Cognitive Servisces.
| text           | String     | Required: The text string to check for spelling and grammar errors.
| mode           | String     | Optional: Mode of spellcheck: *Proof - Meant to provide Office Word like spelling corrections. It can correct long queries, provide casing corrections and suppresses aggressive corrections. *Spell - Meant to provide Search engine like spelling corrections. It will correct small queries(up to length 9 tokens) without any casing changes and will be more optimized (perf and relevance) towards search like queries.

