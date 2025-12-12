package com.brasibturger.services;

import com.brasibturger.dtos.PaiementDTO;
import com.brasibturger.exceptions.ApiException;
import com.brasibturger.models.Paiement;
import com.brasibturger.models.Commande;
import com.brasibturger.repositories.PaiementRepository;
import com.brasibturger.repositories.CommandeRepository;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.stream.Collectors;

@Service
@RequiredArgsConstructor
@Transactional
@Slf4j
public class PaiementService {
    
    private final PaiementRepository paiementRepository;
    private final CommandeRepository commandeRepository;
    
    public List<PaiementDTO> getAllPaiements() {
        log.info("Retrieving all paiements");
        return paiementRepository.findAll().stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public PaiementDTO getPaiementById(Long id) {
        log.info("Retrieving paiement with id: {}", id);
        Paiement paiement = paiementRepository.findById(id)
                .orElseThrow(() -> new ApiException("Paiement not found with id: " + id, 404));
        return convertToDTO(paiement);
    }
    
    public PaiementDTO getPaiementByCommande(Long commandeId) {
        log.info("Retrieving paiement for commande: {}", commandeId);
        Paiement paiement = paiementRepository.findByCommandeIdCommande(commandeId)
                .orElseThrow(() -> new ApiException("Paiement not found for commande: " + commandeId, 404));
        return convertToDTO(paiement);
    }
    
    public PaiementDTO createPaiement(PaiementDTO paiementDTO) {
        log.info("Creating new paiement for commande: {}", paiementDTO.getIdCommande());
        
        Commande commande = commandeRepository.findById(paiementDTO.getIdCommande())
                .orElseThrow(() -> new ApiException("Commande not found with id: " + paiementDTO.getIdCommande(), 404));
        
        Paiement paiement = convertToEntity(paiementDTO);
        paiement.setCommande(commande);
        paiement.setStatut(Paiement.Statut.REUSSI);
        
        Paiement saved = paiementRepository.save(paiement);
        return convertToDTO(saved);
    }
    
    public PaiementDTO updatePaiement(Long id, PaiementDTO paiementDTO) {
        log.info("Updating paiement with id: {}", id);
        Paiement paiement = paiementRepository.findById(id)
                .orElseThrow(() -> new ApiException("Paiement not found with id: " + id, 404));
        
        paiement.setMontant(paiementDTO.getMontant());
        paiement.setMethode(Paiement.Methode.valueOf(paiementDTO.getMethode()));
        paiement.setStatut(Paiement.Statut.valueOf(paiementDTO.getStatut()));
        paiement.setReferencePaiement(paiementDTO.getReferencePaiement());
        
        Paiement updated = paiementRepository.save(paiement);
        return convertToDTO(updated);
    }
    
    public void deletePaiement(Long id) {
        log.info("Deleting paiement with id: {}", id);
        if (!paiementRepository.existsById(id)) {
            throw new ApiException("Paiement not found with id: " + id, 404);
        }
        paiementRepository.deleteById(id);
    }
    
    private PaiementDTO convertToDTO(Paiement paiement) {
        return PaiementDTO.builder()
                .idCommande(paiement.getCommande() != null ? paiement.getCommande().getIdCommande() : null)
                .montant(paiement.getMontant())
                .methode(paiement.getMethode().toString())
                .statut(paiement.getStatut().toString())
                .referencePaiement(paiement.getReferencePaiement())
                .build();
    }
    
    private Paiement convertToEntity(PaiementDTO paiementDTO) {
        return Paiement.builder()
                .montant(paiementDTO.getMontant())
                .methode(Paiement.Methode.valueOf(paiementDTO.getMethode()))
                .referencePaiement(paiementDTO.getReferencePaiement())
                .statut(Paiement.Statut.valueOf(paiementDTO.getStatut() != null ? paiementDTO.getStatut() : "REUSSI"))
                .build();
    }
}
