This is a *very* basic attempt at implementing a basic notification provider for WHMCS. It is current untested and unknown if working.

I will test this out eventually.

All that I want this to do is post a message to a Discord channel to alert staff when an event occurs. At current, it does not tailor the alert to the events content. This will be implemented another time once I reverse engineer some existing modules.

As I know this will come up for those looking for a bridge between whcms and Discord, I'd like to add that such functionality would require a Discord bot to be monitoring the contents of a channel and publishing back to WHMCS on interaction. This is something that I will code, however Discord has fucked over DiscordPy and my desire to support the platform wanes.

This is done out of neccessity, not love. I hate WHMCS and I hate Discord.

PR's will be accepted.