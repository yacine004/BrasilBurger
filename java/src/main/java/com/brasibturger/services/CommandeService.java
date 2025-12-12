package com.brasibturger.services;

import com.brasibturger.dtos.CommandeDTO;
import com.brasibturger.exceptions.ApiException;
import com.brasibturger.models.Commande;
import com.brasibturger.models.Client;
import com.brasibturger.models.Zone;
import com.brasibturger.models.Livreur;
import com.brasibturger.repositories.CommandeRepository;
import com.brasibturger.repositories.ClientRepository;
import com.brasibturger.repositories.ZoneRepository;
import com.brasibturger.repositories.LivreurRepository;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.time.LocalDateTime;
import java.util.List;
import java.util.stream.Collectors;

@Service
@RequiredArgsConstructor
@Transactional
@Slf4j
public class CommandeService {
    
    private final CommandeRepository commandeRepository;
    private final ClientRepository clientRepository;
    private final ZoneRepository zoneRepository;
    private final LivreurRepository livreurRepository;
    
    public List<CommandeDTO> getAllCommandes() {
        log.info("Retrieving all commandes");
        return commandeRepository.findAll().stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public List<CommandeDTO> getCommandesByClient(Long clientId) {
        log.info("Retrieving commandes for client: {}", clientId);
        return commandeRepository.findByClientIdClient(clientId).stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public List<CommandeDTO> getCommandesByStatut(String etat) {
        log.info("Retrieving commandes with etat: {}", etat);
        return commandeRepository.findByEtat(etat).stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public List<CommandeDTO> getCommandesByTypeLivraison(String typeLivraison) {
        log.info("Retrieving commandes with type livraison: {}", typeLivraison);
        return commandeRepository.findByTypeLivraison(typeLivraison).stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public CommandeDTO getCommandeById(Long id) {
        log.info("Retrieving commande with id: {}", id);
        Commande commande = commandeRepository.findById(id)
                .orElseThrow(() -> new ApiException("Commande not found with id: " + id, 404));
        return convertToDTO(commande);
    }
    
    public CommandeDTO createCommande(CommandeDTO commandeDTO) {
        log.info("Creating new commande for client: {}", commandeDTO.getIdClient());
        
        Client client = clientRepository.findById(commandeDTO.getIdClient())
                .orElseThrow(() -> new ApiException("Client not found with id: " + commandeDTO.getIdClient(), 404));
        
        Zone zone = null;
        if (commandeDTO.getIdZone() != null) {
            zone = zoneRepository.findById(commandeDTO.getIdZone())
                    .orElseThrow(() -> new ApiException("Zone not found with id: " + commandeDTO.getIdZone(), 404));
        }
        
        Livreur livreur = null;
        if (commandeDTO.getIdLivreur() != null) {
            livreur = livreurRepository.findById(commandeDTO.getIdLivreur())
                    .orElseThrow(() -> new ApiException("Livreur not found with id: " + commandeDTO.getIdLivreur(), 404));
        }
        
        Commande commande = convertToEntity(commandeDTO);
        commande.setClient(client);
        commande.setZone(zone);
        commande.setLivreur(livreur);
        commande.setEtat(Commande.Etat.VALIDE);
        commande.setDateCreation(LocalDateTime.now());
        
        Commande saved = commandeRepository.save(commande);
        return convertToDTO(saved);
    }
    
    public CommandeDTO updateCommande(Long id, CommandeDTO commandeDTO) {
        log.info("Updating commande with id: {}", id);
        Commande commande = commandeRepository.findById(id)
                .orElseThrow(() -> new ApiException("Commande not found with id: " + id, 404));
        
        if (commandeDTO.getIdClient() != null) {
            Client client = clientRepository.findById(commandeDTO.getIdClient())
                    .orElseThrow(() -> new ApiException("Client not found with id: " + commandeDTO.getIdClient(), 404));
            commande.setClient(client);
        }
        
        commande.setMontantTotal(commandeDTO.getMontantTotal());
        commande.setEtat(Commande.Etat.valueOf(commandeDTO.getEtat()));
        commande.setTypeLivraison(Commande.TypeLivraison.valueOf(commandeDTO.getTypeLivraison()));
        commande.setNotes(commandeDTO.getNotes());
        
        if (commandeDTO.getIdZone() != null) {
            Zone zone = zoneRepository.findById(commandeDTO.getIdZone())
                    .orElseThrow(() -> new ApiException("Zone not found with id: " + commandeDTO.getIdZone(), 404));
            commande.setZone(zone);
        }
        
        if (commandeDTO.getIdLivreur() != null) {
            Livreur livreur = livreurRepository.findById(commandeDTO.getIdLivreur())
                    .orElseThrow(() -> new ApiException("Livreur not found with id: " + commandeDTO.getIdLivreur(), 404));
            commande.setLivreur(livreur);
        }
        
        Commande updated = commandeRepository.save(commande);
        return convertToDTO(updated);
    }
    
    public void deleteCommande(Long id) {
        log.info("Deleting commande with id: {}", id);
        if (!commandeRepository.existsById(id)) {
            throw new ApiException("Commande not found with id: " + id, 404);
        }
        commandeRepository.deleteById(id);
    }
    
    private CommandeDTO convertToDTO(Commande commande) {
        return CommandeDTO.builder()
                .idClient(commande.getClient() != null ? commande.getClient().getIdClient() : null)
                .montantTotal(commande.getMontantTotal())
                .etat(commande.getEtat() != null ? commande.getEtat().toString() : null)
                .typeLivraison(commande.getTypeLivraison() != null ? commande.getTypeLivraison().toString() : null)
                .idZone(commande.getZone() != null ? commande.getZone().getIdZone() : null)
                .idLivreur(commande.getLivreur() != null ? commande.getLivreur().getIdLivreur() : null)
                .notes(commande.getNotes())
                .build();
    }
    
    private Commande convertToEntity(CommandeDTO commandeDTO) {
        return Commande.builder()
                .montantTotal(commandeDTO.getMontantTotal())
                .etat(Commande.Etat.valueOf(commandeDTO.getEtat()))
                .typeLivraison(Commande.TypeLivraison.valueOf(commandeDTO.getTypeLivraison()))
                .notes(commandeDTO.getNotes())
                .build();
    }
}
