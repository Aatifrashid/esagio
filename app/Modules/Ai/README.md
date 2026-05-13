# AI Module (Future)

This directory is a placeholder for the future AI module.

## Planned approach
- Bring-your-own-API-key: clinics provide their own API key
- Costs flow directly to the clinic's API account
- No Esagio-side AI costs

## Potential features
- Auto-generate treatment descriptions from procedure codes
- Smart pricing suggestions based on market data
- Patient communication draft generation
- Treatment outcome predictions

## Implementation notes
- No AI SDK dependencies in v1
- This module will be built as a self-contained Laravel service provider
- Feature-gated to Professional+ tiers
